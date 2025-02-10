<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import AudioControls from './components/AudioControls.vue';
import { WebSocketService } from './services/WebSocketService.ts';
import type { AudioState } from './types/AudioTypes';

const state = ref<AudioState>({
  isRecording: false,
  isPlaying: false,
  selectedLanguage: 'en-US',
  transcript: '',
  receivedTranscript: '',
  receivedAudioUrl: null
});

const mediaRecorder = ref<MediaRecorder | null>(null);
const webSocketService = new WebSocketService();
const recognition = ref<SpeechRecognition | null>(null);

onMounted(async () => {
  try {
    await webSocketService.connect();
    setupSpeechRecognition();
    
    webSocketService.onMessage((event) => {
      const response = JSON.parse(event.data);
      if (response.type === 'text') {
        state.value.receivedTranscript = response.data;
      } else if (response.type === 'audio') {
        // Convert received audio data to URL for playback
        const blob = new Blob([response.data], { type: 'audio/wav' });
        state.value.receivedAudioUrl = URL.createObjectURL(blob);
        playReceivedAudio();
      }
    });
  } catch (error) {
    console.error('Failed to initialize:', error);
  }
});

onUnmounted(() => {
  webSocketService.disconnect();
  if (recognition.value) {
    recognition.value.stop();
  }
});

function setupSpeechRecognition() {
  recognition.value = new webkitSpeechRecognition();
  recognition.value.continuous = true;
  recognition.value.interimResults = true;
  
  recognition.value.onresult = (event) => {
    const transcript = Array.from(event.results)
      .map(result => result[0].transcript)
      .join('');
    
    state.value.transcript = transcript;
    webSocketService.sendTranscript(transcript, state.value.selectedLanguage);
  };
}

async function toggleRecording() {
  if (state.value.isRecording) {
    stopRecording();
  } else {
    await startRecording();
  }
}

async function startRecording() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder.value = new MediaRecorder(stream);
    
    mediaRecorder.value.ondataavailable = (event) => {
      if (event.data.size > 0) {
        event.data.arrayBuffer().then(buffer => {
          webSocketService.sendAudioData(buffer, state.value.selectedLanguage);
        });
      }
    };

    mediaRecorder.value.start(100); // Send audio data every 100ms
    recognition.value?.start();
    state.value.isRecording = true;
  } catch (error) {
    console.error('Error starting recording:', error);
  }
}

function stopRecording() {
  if (mediaRecorder.value) {
    mediaRecorder.value.stop();
    mediaRecorder.value.stream.getTracks().forEach(track => track.stop());
    recognition.value?.stop();
    state.value.isRecording = false;
  }
}

function playReceivedAudio() {
  if (state.value.receivedAudioUrl) {
    const audio = new Audio(state.value.receivedAudioUrl);
    audio.play();
  }
}
</script>

<template>
  <div class="app-container">
    <h1>Audio Streaming & Translation</h1>
    
    <AudioControls
      v-model:selectedLanguage="state.selectedLanguage"
      :isRecording="state.isRecording"
      @toggleRecording="toggleRecording"
    />

    <div class="transcripts">
      <div class="transcript-box">
        <h3>Your Speech</h3>
        <p>{{ state.transcript || 'Start speaking...' }}</p>
      </div>

      <div class="transcript-box">
        <h3>Received Translation</h3>
        <p>{{ state.receivedTranscript || 'Waiting for translation...' }}</p>
      </div>
    </div>
  </div>
</template>

<style>
.app-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  text-align: center;
  color: #333;
  margin-bottom: 40px;
}

.transcripts {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-top: 40px;
}

.transcript-box {
  background: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.transcript-box h3 {
  margin-top: 0;
  color: #333;
  border-bottom: 2px solid #eee;
  padding-bottom: 10px;
}

.transcript-box p {
  min-height: 100px;
  white-space: pre-wrap;
  color: #666;
}
</style>