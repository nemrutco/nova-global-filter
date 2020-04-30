Nova.booting((Vue, router, store) => {
  Vue.component('partition-metric', require('./components/PartitionMetric'))
  Vue.component('trend-metric', require('./components/TrendMetric'))
  Vue.component('value-metric', require('./components/ValueMetric'))
  Vue.component('nova-global-filter', require('./components/GlobalFilter'))
})
