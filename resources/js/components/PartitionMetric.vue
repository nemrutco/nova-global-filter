<script>
import Partitionmetric from "../../../../../vendor/laravel/nova/resources/js/components/Metrics/Partitionmetric";
import FilterBehavior from "./FilterBehavior";
import { Minimum } from "laravel-nova";

export default {
  extends: Partitionmetric,
  mixins: [FilterBehavior],
  methods: {
    fetch() {
      this.loading = true;

      Minimum(
        Nova.request().get(this.metricEndpoint, this.filterPayload())
      ).then(
        ({
          data: {
            value: { value }
          }
        }) => {
          this.chartData = value;
          this.loading = false;
        }
      );
    }
  }
};
</script>