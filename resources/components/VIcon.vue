<script lang="ts">
import { defineComponent, onBeforeMount, ref } from "vue";

export default defineComponent({
    props: ['name'],
    setup(props) {
        let icon = ref<string>("");

        onBeforeMount(() => {
            fetch("/icons/" + props.name + ".svg")
                .then((res) => res.text())
                .then((data) => {
                    let element = document.createElement('div');
                    element.innerHTML = data;
                    icon.value = element.firstElementChild?.innerHTML ?? "";
                });
        });

        return { icon };
    },
});
</script>

<template>
    <svg
        ref="placeholder"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        v-html="icon"
    ></svg>
</template>
