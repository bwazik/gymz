importScripts(
    "https://www.gstatic.com/firebasejs/10.8.1/firebase-app-compat.js",
);
importScripts(
    "https://www.gstatic.com/firebasejs/10.8.1/firebase-messaging-compat.js",
);

const firebaseConfig = {
    apiKey: "AIzaSyCYa0KNBT6eh6QIbHrSkePViG97khXVXyk",
    authDomain: "gymz-d00e0.firebaseapp.com",
    projectId: "gymz-d00e0",
    storageBucket: "gymz-d00e0.firebasestorage.app",
    messagingSenderId: "923646912477",
    appId: "1:923646912477:web:75faa50fa6518274c27834",
    measurementId: "G-DRP43LBB2B",
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );

    const notificationTitle = payload.notification?.title || "GymZ";
    const notificationOptions = {
        body: payload.notification?.body,
        icon: "/icons/icon-192x192.png",
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
