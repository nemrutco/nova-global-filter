<template>
  <div class="h-auto md:col-span-12">
    <div class="mb-4 flex justify-end items-center" v-if="card.resettable">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 text-base rounded bg-90 text-center cursor-pointer shadow-md max-w-full ml-auto"
        @click="resetFilters(card.filters)"
      >{{ __('Reset') }}</button>
    </div>
    <div
      v-if="card.filters.length > 0"
      class="bg-30 border-b border-60 rounded-lg shadow h-auto"
    >
      <scroll-wrap class="flex-wrap bg-white" :class="{ 'flex w-auto': card.inline, 'w-1/3': !card.inline }">
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
              class="w-full form-control form-select"
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
  mounted() {
    //this.$parent.$el.classList.remove("w-5/6");
    //this.$parent.$el.classList.add("w-full");
  },
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
