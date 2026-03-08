import "./bootstrap";
import { messaging } from "./firebase";
import { getToken } from "firebase/messaging";

window.requestFCMToken = async () => {
    try {
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
            const registration =
                await navigator.serviceWorker.register("/sw.js");

            // Wait until it's active
            await navigator.serviceWorker.ready;

            const currentToken = await getToken(messaging, {
                vapidKey:
                    "BMTSUTpn_bU5EcscMRFo3bBLGCFNzUyVlF1h1tnAKDlU-IaKvhVVmkNYelAvnf9VKbhgZ_7yV5gpKBBxSKx93a0",
                serviceWorkerRegistration: registration,
            });

            if (currentToken) {
                console.log("FCM Token:", currentToken);

                // Send token to our Laravel Backend
                try {
                    await axios.post("/fcm-token", {
                        token: currentToken,
                    });
                    console.log(
                        "Token synchronized with backend successfully.",
                    );
                } catch (backendError) {
                    console.error(
                        "Failed to sync token with backend.",
                        backendError,
                    );
                }
            } else {
                console.log(
                    "No registration token available. Request permission to generate one.",
                );
            }
        } else {
            console.error("Notification permission denied.");
        }
    } catch (error) {
        console.error("An error occurred while retrieving token:", error);
    }
};
