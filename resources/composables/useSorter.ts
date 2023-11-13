import { router } from "@inertiajs/vue3";

export function useSorter(sortkey: string = 'sort') {
  function ltrim(string: string, char: string) {
    const first = [...string].findIndex((a) => a !== char);
    return string.substring(first, string.length);
  }

  function isCurrent(column: string) {
    const urlParams = new URLSearchParams(window.location.search);
    return ltrim(urlParams.get(sortkey) ?? "", "-") === column;
  }

  function isDesc(column: string) {
    const urlParams = new URLSearchParams(window.location.search);

    let handle = urlParams.get(sortkey) ?? "";

    return handle.includes("-");
  }

  function sortBy(column: string) {
    const urlParams = new URLSearchParams(window.location.search);

    let current = urlParams.get(sortkey) ?? "";
    let params: any = { [sortkey]: current };

    if (ltrim(current, "-") == column) {
      if (current.startsWith("-")) {
        params[sortkey] = undefined;
      } else {
        params[sortkey] = "-" + column;
      }
    } else {
      params[sortkey] = column;
    }

    router.get(location.href, params, { replace: true });
  }

  return { sortBy, isCurrent, isDesc };
}
