declare module "*.vue" {
    import { defineComponent } from "vue";
    export default typeof defineComponent;
}
