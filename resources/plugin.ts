import { merge } from "lodash";
import { App, defineAsyncComponent, markRaw } from "vue";
import { useTableStore } from "~/stores/useTableStore";

function getAsync(path: any) {
    return markRaw(
        defineAsyncComponent({
            loader: () => import(path),
        })
    );
}

const harmony = {
    install(app: App, options: any) {
        const store = useTableStore();
        const defaultColumns = {
            text: getAsync("./components/VColumnText.vue"),
            link: getAsync("./components/VColumnLink.vue"),
            bool: getAsync("./components/VColumnBool.vue"),
            actions: getAsync("./components/VColumnActions.vue"),
        };
        store.setColumns(merge(defaultColumns, options.columns));
    },
};

export { harmony };
