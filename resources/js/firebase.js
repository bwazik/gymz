import { initializeApp } from "firebase/app";
import { getMessaging } from "firebase/messaging";

const firebaseConfig = {
    apiKey: "AIzaSyDhFdsXNsQy5erfcuLwCzJZuH2kcWwRxws",
    authDomain: "gymz-d00e0.firebaseapp.com",
    projectId: "gymz-d00e0",
    storageBucket: "gymz-d00e0.firebasestorage.app",
    messagingSenderId: "923646912477",
    appId: "1:923646912477:web:75faa50fa6518274c27834",
    measurementId: "G-DRP43LBB2B",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging and get a reference to the service
const messaging = getMessaging(app);

export { app, messaging };
