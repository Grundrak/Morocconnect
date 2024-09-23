<template>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
      <div class="card">
        <div class="card-body text-center statistics-info">
          <div :class="['counter-icon', bgClass, 'mb-0', 'box-primary-shadow']">
            <i :class="[icon, 'text-white']"></i>
          </div>
          <h6 class="mt-4 mb-1">{{ title }}</h6>
          <h2 class="mb-2 number-font">{{ value }}</h2>
          <p class="text-muted">{{ description }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      title: String,
      value: String,
      icon: String,
      bgClass: String,
      description: String,
    },
  };
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>