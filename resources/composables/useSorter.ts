import { router } from "@inertiajs/vue3";

export function useSorter() {
  function ltrim(string: string, char: string) {
    const first = [...string].findIndex((a) => a !== char);
    return string.substring(first, string.length);
  }

  function isCurrent(column: string) {
    const urlParams = new URLSearchParams(window.location.search);
    return ltrim(urlParams.get("table_sort") ?? "", "-") === column;
  }

  function isDesc(column: string) {
    const urlParams = new URLSearchParams(window.location.search);

    let handle = urlParams.get("table_sort") ?? "";

    return handle.includes("-");
  }

  function sortBy(column: string) {
    const urlParams = new URLSearchParams(window.location.search);

    let current = urlParams.get("table_sort") ?? "";
    let params: any = { table_sort: current };

    if (ltrim(current, "-") == column) {
      if (current.startsWith("-")) {
        params.table_sort = undefined;
      } else {
        params.table_sort = "-" + column;
      }
    } else {
      params.table_sort = column;
    }

    router.get(location.href, params, { replace: true });
  }

  return { sortBy, isCurrent, isDesc };
}
