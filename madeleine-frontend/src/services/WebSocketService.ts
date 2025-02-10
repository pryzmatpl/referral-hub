export class WebSocketService {
  private ws: WebSocket | null = null;
  private url: string;

  constructor() {
    this.url = 'wss://madeline.com/audio-stream';
  }

  connect(): Promise<void> {
    return new Promise((resolve, reject) => {
      this.ws = new WebSocket(this.url);
      
      this.ws.onopen = () => {
        console.log('WebSocket connected');
        resolve();
      };

      this.ws.onerror = (error) => {
        console.error('WebSocket error:', error);
        reject(error);
      };
    });
  }

  sendAudioData(audioData: ArrayBuffer, language: string) {
    if (this.ws?.readyState === WebSocket.OPEN) {
      const message = {
        type: 'audio',
        data: audioData,
        language
      };
      this.ws.send(JSON.stringify(message));
    }
  }

  sendTranscript(text: string, language: string) {
    if (this.ws?.readyState === WebSocket.OPEN) {
      const message = {
        type: 'text',
        data: text,
        language
      };
      this.ws.send(JSON.stringify(message));
    }
  }

  onMessage(callback: (event: MessageEvent) => void) {
    if (this.ws) {
      this.ws.onmessage = callback;
    }
  }

  disconnect() {
    if (this.ws) {
      this.ws.close();
      this.ws = null;
    }
  }
}