import Shepherd from "shepherd.js";

document.addEventListener("DOMContentLoaded", () => {
    if (!localStorage.getItem("gymz_tour_completed")) {
        const tour = new Shepherd.Tour({
            useModalOverlay: true,
            defaultStepOptions: {
                classes:
                    "bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 shadow-2xl text-gray-900 dark:text-white",
                scrollTo: true,
                cancelIcon: {
                    enabled: false,
                },
            },
        });

        tour.addStep({
            id: "welcome",
            title: "أهلاً بيك في GymZ 🏋🏽",
            text: '<p class="text-gray-500 dark:text-gray-400 text-sm">خلينا ناخد جولة سريعة في 10 ثواني عشان تعرف إزاي تستخدم الأبلكيشن.</p>',
            classes:
                "shepherd-centered bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 shadow-2xl text-gray-900 dark:text-white",
            buttons: [
                {
                    text: "يلا بينا",
                    classes:
                        "bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md",
                    action: tour.next,
                },
            ],
        });

        tour.addStep({
            id: "create",
            title: "إضافة التمارين ⚡",
            text: '<p class="text-gray-500 dark:text-gray-400 text-sm">من هنا تقدر تنزل تمرينة جديدة وتحدد الجيم والوقت والهدف العضلي عشان أي حد يقدر ينضم ليك.</p>',
            attachTo: {
                element: "#tour-create-btn",
                on: "top",
            },
            buttons: [
                {
                    text: "التالي",
                    classes:
                        "bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md",
                    action: tour.next,
                },
            ],
        });

        tour.addStep({
            id: "feed",
            title: "التمارين القريبة منك 🗺️",
            text: '<p class="text-gray-500 dark:text-gray-400 text-sm">هنا بتظهر التمارين اللي اتنشرت في منطقتك وتقدر تبعت طلب انضمام لأي تمرينة.</p>',
            attachTo: {
                element: "#tour-feed",
                on: "bottom",
            },
            buttons: [
                {
                    text: "التالي",
                    classes:
                        "bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md",
                    action: tour.next,
                },
            ],
        });

        tour.addStep({
            id: "requests",
            title: "طلبات التمرين 🤝",
            text: '<p class="text-gray-500 dark:text-gray-400 text-sm">هنا بتشوف طلبات التمارين اللي وصلتك أو اللي بعتها لتمارين تانية.</p>',
            attachTo: {
                element: "#tour-nav-requests",
                on: "top",
            },
            buttons: [
                {
                    text: "التالي",
                    classes:
                        "bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md",
                    action: tour.next,
                },
            ],
        });

        tour.addStep({
            id: "sessions",
            title: "تأكيد التمرين (QR) 📷",
            text: '<p class="text-gray-500 dark:text-gray-400 text-sm">هنا بتتم التمرينة الفعلية! بتأكد حضورك بالكيو آر كود وتبدأ التمرينة عشان تكسب النقط.</p>',
            attachTo: {
                element: "#tour-nav-sessions",
                on: "top",
            },
            buttons: [
                {
                    text: "التالي",
                    classes:
                        "bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md",
                    action: tour.next,
                },
            ],
        });

        tour.addStep({
            id: "profile",
            title: "بروفايلك ومحفظتك 🍑",
            text: '<p class="text-gray-500 dark:text-gray-400 text-sm">آخر صفحة فيها تاريخ تمارينك، نسبة الثقة، محفظة الجلوتس، المتجر، ورابط الإحالة بتاعك.</p>',
            attachTo: {
                element: "#tour-nav-profile",
                on: "top",
            },
            buttons: [
                {
                    text: "ابدأ الرحلة 🚀",
                    classes:
                        "bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md",
                    action: () => {
                        localStorage.setItem("gymz_tour_completed", "true");
                        tour.complete();
                    },
                },
            ],
        });

        setTimeout(() => {
            if (!localStorage.getItem("gymz_tour_completed")) {
                tour.start();
            }
        }, 1000);
    }
});
