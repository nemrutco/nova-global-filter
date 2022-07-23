<script>
import Partitionmetric from "@/components/Metrics/PartitionMetric";
import FilterBehavior from "./FilterBehavior";
import  {minimum}  from "@/util";

export default {
  extends: Partitionmetric,
  mixins: [FilterBehavior],
  methods: {
    fetch() {
      this.loading = true;

      minimum(
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