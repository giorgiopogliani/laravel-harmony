import { defineStore } from "pinia";
import { computed, reactive } from "vue";

export type Notification = {
    id?: string;
    title: string | any;
    type: "success" | "error" | "info";
    description?: string;
};

export type Notifications = {
    notifications: Notification[];
};

export const useNotificationStore = defineStore("notifications", () => {
    const state = reactive<
        {
            timeout: any;
            notification: Notification;
        }[]
    >([]);

    function send(notification: Notification) {
        let timeout = setTimeout(() => state.filter((o) => o.notification.title === notification.title), 5000);

        state.push({
            timeout: timeout,
            notification: {
                id: Math.random().toString(36),
                title: notification.title,
                type: notification.type,
            },
        });
    }

    function dismiss(notification: Notification) {
        state
            .filter((o) => o.notification.id === notification.id)
            .forEach((o) => {
                clearTimeout(o.timeout);
                state.splice(state.indexOf(o), 1);
            });
    }

    return {
        items: computed(() => state.map((o) => o.notification)),
        dismiss,
        send,
    };
});
