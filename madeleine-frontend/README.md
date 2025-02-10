# Audio Streaming & Translation App

## Overview
This project is a real-time audio streaming and translation application built with **Vue 3**, **TypeScript**, and **Vite**. The application allows users to stream audio, transcribe speech to text, translate it, and receive processed audio from a WebSocket server.

## Features
### Audio Controls
- **Record Button** – Start and stop real-time audio streaming
- **Language Selection** – Choose a target language for translation
- **Audio Visualization** – Integrates **WaveSurfer.js** for a dynamic visual representation

### WebSocket Communication
- **Server Connection** – Connects to `wss://madeline.com/audio-stream`
- **Bidirectional Streaming** – Supports streaming both **audio** and **text**
- **Data Management** – Handles different message types efficiently

### Speech Recognition
- **Web Speech API** – Enables client-side **speech-to-text**
- **Continuous Recognition** – Captures real-time speech with **interim results**
- **Text Streaming** – Streams transcribed text to the server in real-time

### Audio Processing
- **MediaRecorder API** – Captures and streams **audio chunks**
- **Real-time Playback** – Plays received audio stream from the server

### User Interface
- **Responsive UI** – Clean, intuitive interface
- **Live Transcription** – Displays transcribed text in real-time
- **Translation Output** – Shows translated text from the server
- **Audio Visualization** – Provides waveform visualization of the audio stream

## How It Works
1. Select a language from the dropdown.
2. Click **Start Recording** to begin streaming audio.
3. Speak into your microphone.
4. View the **real-time transcript** and **translated text**.
5. Listen to the **processed audio** received from the server.

## Technical Details
### Client-Side (Vue 3 + Webpack)
- Vue 3 with `<script setup>` for better performance and maintainability.
- Uses WebSockets for real-time communication.
- Implements **WaveSurfer.js** for audio visualization.
- Utilizes the Web Speech API for in-browser speech recognition.

### Server-Side (Go)
The backend server is expected to:
- Receive and process **audio and text streams**.
- Perform **speech recognition and translation**.
- Send back **processed audio** and translated text.

## Development & Setup
### Prerequisites
Ensure you have the following installed:
- **Node.js** (Latest LTS recommended)
- **Vue 3 + Vite**
- **Go** (for the backend server)

### Installation
```sh
# Clone the repository
git clone https://github.com/your-repo/audio-streaming-app.git
cd audio-streaming-app

# Install dependencies
npm install

# Start the development server
npm run dev
```

### Server Implementation
You need to implement a WebSocket server in **Go** that:
- Accepts WebSocket connections at `wss://madeline.com/audio-stream`
- Handles incoming **audio and text streams**
- Processes the data and returns **translated text and audio**

## Future Enhancements
- **Support for additional languages**
- **Improved UI design with animations**
- **More robust WebSocket error handling**
- **AI-driven voice synthesis for enhanced audio playback**

## Contributing
Contributions are welcome! Feel free to submit **issues** and **pull requests**.

## License
This project is licensed under the [MIT License](LICENSE).

