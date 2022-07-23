<template>
  <div v-if="card.filters.length > 0" class="h-auto md:col-span-12">
    <div class="mb-4 flex" v-if="card.resettable">
      <h1
        class="text-90 font-normal text-xl md:text-2xl mb-3 items-center mt-6" 
      >
        <span>{{ card.title }}</span>
      </h1>
      <div class="justify-end items-center ml-auto mr-0 self-end">
        <button
          class="shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm flex-shrink-0"
          @click="resetFilters(card.filters)"
        >
          {{ __("Reset") }}
        </button>
      </div>
    </div>
    <div
      v-if="card.filters.length > 0"
      class="bg-30 border-b border-60 rounded-lg shadow h-auto"
    >
      <scroll-wrap
        class="flex-wrap bg-white"
        :class="{ 'flex w-auto': card.inline, 'w-1/3': !card.inline }"
      >
        <div
          v-for="(filter, index) in card.filters"
          class="w-auto"
          :key="index"
        >
          <div class="px-8 py-6">
            <label
              :for="filter.name"
              class="block mb-3 mr-3 text-80 pt-2 leading-tight whitespace-nowrap"
              >{{ filter.name }}</label
            >
            <input
              v-if="filter.component === 'date-filter'"
              type="date"
              class="w-full form-control form-input form-input-bordered"
              ref="dateTimePicker"
              :id="filter.name"
              dusk="date-filter"
              name="date-filter"
              :value="filter.value || filter.currentValue"
              :class="errorClasses"
              @input.prevent=""
              @change="handleChange(filter, $event)"
            />

            <div
              v-if="filter.component === 'boolean-filter'"
              class="flex flex-wrap"
            >
              <checkbox-with-label
                :class="{
                  'flex mr-3 -mb-2 pb-3 w-auto': card.inline,
                  'w-full mt-2': !card.inline,
                }"
                v-for="option in filter.options"
                :key="option.name"
                :name="option.name"
                :checked="option.checked"
                @input="handleChange(filter, $event)"
                >{{ option.name }}</checkbox-with-label
              >
            </div>

            <select
              :id="filter.name"
              v-if="filter.component === 'select-filter'"
              @change="handleChange(filter, $event)"
              class="w-full form-control form-select form-input-bordered"
            >
              <option
                value
                selected
                v-if="!filter.currentValue && filter.currentValue !== 0"
              >
                &mdash;
              </option>
              <option
                v-for="option in filter.options"
                :key="option.value"
                :value="option.value"
                :selected="
                  option.value === filter.value ||
                  option.value === filter.currentValue
                "
              >
                {{ option.label }}
              </option>
            </select>
          </div>
        </div>
      </scroll-wrap>
    </div>
  </div>
</template>

<script>
import { Filterable, InteractsWithQueryString } from "@/mixins";

export default {
  mixins: [Filterable, InteractsWithQueryString],
  props: {
    card: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    selectedCheckboxs: {
      type: Object,
    },
  }),
  created() {
    Nova.$on("global-filter-request", (filterClasses) => {
      let filters = this.card.filters !== undefined ? this.card.filters : [];

      if (filterClasses && filterClasses.length) {
        filters = filters.filter((filter) =>
          filterClasses.includes(filter.class)
        );
      }
      Nova.$emit("global-filter-response", filters);
    });
  },
  methods: {
    handleChange(filter, event) {
      let value = event;
      if (typeof event === "object") {
        value = event.target.value;
      }

      if (filter.component === "boolean-filter") {
        if (event.target.checked) {
          this.selectedCheckboxs[event.target.name] = 1;
        } else {
          delete this.selectedCheckboxs[event.target.name];
        }
        value = this.selectedCheckboxs;
      }

      if (filter.currentValue !== value) {
        filter.currentValue = value;
        Nova.$emit("global-filter-changed", filter);
      }
    },
    resetFilters() {
      window.location.reload();
    },
  },
};
</script>
