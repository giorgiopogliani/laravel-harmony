import { merge } from "lodash";
import { App } from "vue";
import { useTableStore } from "~/stores/useTableStore";

import VColumnText from "~/components/VColumnText.vue";
import VColumnLink from "~/components/VColumnLink.vue";
import VColumnBool from "~/components/VColumnBool.vue";
import VColumnActions from "~/components/VColumnActions.vue";

// function getAsync(path: any) {
//     return markRaw(
//         defineAsyncComponent({
//             loader: () => import(path),
//         })
//     );
// }

const harmony = {
    install(app: App, options: any) {
        const store = useTableStore();
        const defaultColumns = {
            text: VColumnText,
            link: VColumnLink,
            bool: VColumnBool,
            actions: VColumnActions,
        };
        store.setColumns(merge(defaultColumns, options.columns));
    },
};

export { harmony };
