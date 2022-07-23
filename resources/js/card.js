Nova.booting((Vue, router, store) => {
     Vue.component('PartitionMetric', require('./components/PartitionMetric').default)
     Vue.component('TrendMetric', require('./components/TrendMetric.vue').default)
     Vue.component('ValueMetric', require('./components/ValueMetric.vue').default)
     Vue.component('NovaGlobalFilter', require('./components/GlobalFilter.vue').default)
})
