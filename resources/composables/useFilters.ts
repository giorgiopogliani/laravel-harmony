import { Filter } from "~/types";
import { computed } from "vue";

export function useFilters(props: { filters: Filter[]; query: any }) {
  const activeFilters = computed<Filter[]>(() => {
    return (
      props.filters.filter((f) => Object.keys(props.query).includes(f.name)) ??
      []
    );
  });

  function addFilter(filter: any) {
    props.query[filter.name] = {
      value: filter.value ?? "",
      operator: Object.keys(filter.operators)[0] ?? "",
    };
  }

  function isStandalone(filter: Filter) {
    return (
      filter.operators.find(
        (op) => op.key === props.query[filter.key]["operator"]
      )?.standalone ?? false
    );
  }

  return {
    activeFilters,
    addFilter,
    isStandalone,
  };
}
