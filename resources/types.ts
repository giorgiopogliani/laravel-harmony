export type Table<Type> = {
  selectable: true;
  columns: Column[];
  rows: Pagination<Type>;
  query: object;
  filters: Filter[];
  actions: {
    active: boolean;
    label: string;
    url: string | null;
  }[];
};

export type Filter = {
  label: string;
  name: string;
  key: string;
  placeholder: string;
  operators: { key: string; label: string; standalone: boolean }[];
  options?: object;
  type: string;
  props: object;
};

export type Form = {
  schema: any[];
  data: any;
};

export type Column = {
  title: string;
  key: string;
  sortable: boolean;
  type: string;
};

export type Pagination<Type> = {
  current_page: number;
  data: Type[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: {
    active: boolean;
    label: string;
    url: string | null;
  }[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
};

export type AnyRow = object & {
  id: number;
};

export type ColumnVisibility = {
  [key: string]: boolean;
};

export type ColumnOptions = {
  [key: string]: string;
};

export type TableConfig = {
  endpoint: string;
  filtersKey: string;
  reload: string[];
};
