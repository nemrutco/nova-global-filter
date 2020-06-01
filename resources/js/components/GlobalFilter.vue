<template>
  <div class="h-auto">
    <div class="mb-4 flex justify-end items-center" v-if="card.resettable">
      <button
        class="btn btn-default btn-primary"
        @click="resetFilters(card.filters)"
      >{{ __('Reset') }}</button>
    </div>
    <div v-if="card.filters.length > 0" class="bg-30 border-b border-60 rounded-lg shadow">
      <scroll-wrap class="flex flex-wrap">
        <div
          v-for="(filter,index) in card.filters"
          :class="{'w-auto':card.inline, 'w-1/3': !card.inline}"
          :key="index"
        >
          <div class="px-8 py-6" :class="{ 'flex': card.inline }">
            <label
              :for="filter.name"
              class="block mb-3 mr-3 text-80 pt-2 leading-tight"
            >{{ filter.name }}</label>
            <date-time-picker
              :id="filter.name"
              v-if="filter.component === 'date-filter'"
              class="w-full form-control form-input form-input-bordered"
              dusk="date-filter"
              name="date-filter"
              autocomplete="off"
              :value="filter.value"
              dateFormat="Y-m-d"
              :placeholder="__('Choose date')"
              :enable-time="false"
              :enable-seconds="false"
              @input.prevent
              @change="handleChange(filter, $event)"
            />
            <div v-if="filter.component === 'boolean-filter'" class="flex flex-wrap">
              <checkbox-with-label
                :class="{ 'flex mr-3 -mb-2 pb-3 w-auto': card.inline, 'w-full mt-2': !card.inline}"
                v-for="option in filter.options"
                :key="option.name"
                :name="option.name"
                :checked="option.checked"
                @input="handleChange(filter, $event)"
              >{{ option.name }}</checkbox-with-label>
            </div>

            <select
              :id="filter.name"
              v-if="filter.component === 'select-filter'"
              @change="handleChange(filter, $event)"
              class="w-full form-control form-select"
            >
              <option
                v-for="option in filter.options"
                :key="option.value"
                :value="option.value"
                :selected="option.value == filter.value"
              >{{ option.name }}</option>
            </select>
          </div>
        </div>
      </scroll-wrap>
    </div>
  </div>
</template>

<script>
import { Filterable, InteractsWithQueryString } from "laravel-nova";

export default {
  mixins: [Filterable, InteractsWithQueryString],
  props: {
    card: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    selectedCheckboxs: {
      type: Object
    }
  }),
  mounted() {
    this.$parent.$el.classList.remove("w-5/6");
    this.$parent.$el.classList.add("w-full");
  },
  created() {
    Nova.$on("global-filter-request", filterClasses => {
      let filters = this.card.filters !== undefined ? this.card.filters : [];

      if (filterClasses && filterClasses.length) {
        filters = filters.filter(filter =>
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

      filter.currentValue = value;
      Nova.$emit("global-filter-changed", filter);
    },
    resetFilters() {
      this.$router.go(this.$router.currentRoute);
    }
  }
};
</script>
