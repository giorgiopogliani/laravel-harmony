import { merge } from "lodash";
import { App } from "vue";
import { useTableStore } from "~/stores/useTableStore";
import VColumnText from "~/components/VColumnText.vue";
import VColumnLink from "~/components/VColumnLink.vue";
import VColumnBool from "~/components/VColumnBool.vue";
import VColumnActions from "~/components/VColumnActions.vue";

const harmony = {
    install(app: App, options: any) {
        const store = useTableStore();

        app.component("VColumnText", VColumnText);
        app.component("VColumnLink", VColumnLink);
        app.component("VColumnBool", VColumnBool);
        app.component("VColumnActions", VColumnActions);

        const defaultColumns = {
            text: 'VColumnText',
            link: 'VColumnLink',
            bool: 'VColumnBool',
            actions: 'VColumnActions',
        };
        store.setColumns(merge(defaultColumns, options.columns));
    },
};

export { harmony };
