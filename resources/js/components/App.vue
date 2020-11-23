<template>
  <div>
      <Navbar></Navbar>
      <router-view></router-view>
      <vue-progress-bar></vue-progress-bar>
  </div>
</template>

<script>

import Navbar from './Navbar'
import ModalEdit from './ModalEdit'

export default {
    components : {
        Navbar,
    }
}
</script
        ModalEdit>

<style>

</style>