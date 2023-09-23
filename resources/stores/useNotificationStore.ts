import * as toast from "@zag-js/toast"
import { normalizeProps, useMachine } from "@zag-js/vue"
import { defineStore } from "pinia";

export type Notification = {
  message: string | any;
  type: "success" | "error" | "info";
};

export type Notifications = {
  notifications: Notification[];
};

export const useNotificationStore = defineStore("notifications", () => {
  const [state, _send] = useMachine(toast.group.machine({ id: "1" }));

  const toastApi = toast.group.connect(state.value, _send, normalizeProps);

  function send(notification: Notification) {
    toastApi.create({
      title: notification.message,
      type: notification.type,
      placement: "top-start",
    });
  }

  return {
    api: toastApi,
    send,
  };
});
