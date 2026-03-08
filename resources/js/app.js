import "./bootstrap";
import { messaging } from "./firebase";
import { getToken } from "firebase/messaging";

window.requestFCMToken = async () => {
    try {
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
            const registration =
                (await navigator.serviceWorker.getRegistration("/sw.js")) ||
                (await navigator.serviceWorker.register("/sw.js"));
            const currentToken = await getToken(messaging, {
                vapidKey:
                    "BKFeCsepkOWdHzCNLFaxstLYrxLAIiCtrwjCnfxaiZF2gIILBxSM_0atcuxlwADrqkHM0joLMNRHQ5KVQ2ugpyM",
                serviceWorkerRegistration: registration,
            });

            if (currentToken) {
                console.log("FCM Token:", currentToken);
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
