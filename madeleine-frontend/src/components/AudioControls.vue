<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import WaveSurfer from 'wavesurfer.js';
import { SUPPORTED_LANGUAGES } from '../types/AudioTypes';

const props = defineProps<{
  isRecording: boolean;
  selectedLanguage: string;
}>();

const emit = defineEmits<{
  'update:selectedLanguage': [value: string];
  'toggleRecording': [];
}>();

const wavesurfer = ref<WaveSurfer | null>(null);
const waveformRef = ref<HTMLDivElement | null>(null);

onMounted(() => {
  if (waveformRef.value) {
    wavesurfer.value = WaveSurfer.create({
      container: waveformRef.value,
      waveColor: '#4CAF50',
      progressColor: '#1976D2',
      cursorColor: '#E91E63',
      height: 100,
    });
  }
});

onUnmounted(() => {
  wavesurfer.value?.destroy();
});
</script>

<template>
  <div class="audio-controls">
    <div class="control-panel">
      <button 
        @click="$emit('toggleRecording')"
        :class="{ 'recording': isRecording }"
      >
        {{ isRecording ? 'Stop Recording' : 'Start Recording' }}
      </button>

      <select 
        :value="selectedLanguage"
        @change="$emit('update:selectedLanguage', ($event.target as HTMLSelectElement).value)"
      >
        <option v-for="lang in SUPPORTED_LANGUAGES" :key="lang.code" :value="lang.code">
          {{ lang.name }}
        </option>
      </select>
    </div>

    <div ref="waveformRef" class="waveform"></div>
  </div>
</template>

<style scoped>
.audio-controls {
  width: 100%;
  max-width: 800px;
  margin: 20px auto;
}

.control-panel {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

button {
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 4px;
  cursor: pointer;
  background-color: #4CAF50;
  color: white;
  border: none;
  transition: background-color 0.3s;
}

button.recording {
  background-color: #f44336;
}

button:hover {
  opacity: 0.9;
}

select {
  padding: 10px;
  font-size: 16px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.waveform {
  width: 100%;
  background: #f5f5f5;
  border-radius: 4px;
  padding: 20px;
}
</style>