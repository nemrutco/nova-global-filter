<script>
import ValueMetric from "@/components/Metrics/ValueMetric";
import FilterBehavior from "./FilterBehavior";
import  {minimum}  from "@/util";

export default {
  extends: ValueMetric,
  mixins: [FilterBehavior],
  methods: {
    fetch() {
      this.loading = true;

      minimum(
        Nova.request().get(this.metricEndpoint, this.filterPayload())
      ).then(
        ({
          data: {
            value: {
              value,
              previous,
              prefix,
              suffix,
              suffixInflection,
              format,
              zeroResult
            }
          }
        }) => {
          this.value = value;
          this.format = format || this.format;
          this.prefix = prefix || this.prefix;
          this.suffix = suffix || this.suffix;
          this.suffixInflection = suffixInflection;
          this.zeroResult = zeroResult || this.zeroResult;
          this.previous = previous;
          this.loading = false;
        }
      );
    }
  }
};
</script>