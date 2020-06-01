export default {
    data: () => ({
        selectedFilters: {
            type: Array
        }
    }),
    created() {
        Nova.$on("global-filter-changed", filter => {
            this.selectedFilters[filter.class] = filter.currentValue;
            if (filter.currentValue === '' || JSON.stringify(filter.currentValue) === JSON.stringify({})) {
                delete this.selectedFilters[filter.class]
            }
            this.fetch();
        });
        Nova.$on("global-filter-reset", filters => {
            this.$router.go(this.$router.currentRoute)
        });
    },

    methods: {
        filterPayload() {
            const payload = {
                params: {
                    timezone: this.userTimezone,
                    twelveHourTime: this.usesTwelveHourTime
                }
            };

            if (this.hasRanges) {
                payload.params.range = this.selectedRangeKey;
            }

            if (JSON.stringify(this.selectedFilters) !== JSON.stringify({})) {
                payload.params.filters = this.selectedFilters;
            }

            return payload;
        }
    }
}
