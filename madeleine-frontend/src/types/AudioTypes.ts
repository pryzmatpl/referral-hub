export interface AudioState {
  isRecording: boolean;
  isPlaying: boolean;
  selectedLanguage: string;
  transcript: string;
  receivedTranscript: string;
  receivedAudioUrl: string | null;
}

export interface WebSocketMessage {
  type: 'audio' | 'text';
  data: string | ArrayBuffer;
  language: string;
}

export const SUPPORTED_LANGUAGES = [
  { code: 'en-US', name: 'English (US)' },
  { code: 'es-ES', name: 'Spanish (Spain)' },
  { code: 'fr-FR', name: 'French (France)' },
  { code: 'de-DE', name: 'German' },
  { code: 'it-IT', name: 'Italian' },
  { code: 'ja-JP', name: 'Japanese' },
];