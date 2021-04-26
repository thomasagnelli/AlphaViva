<template>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Nome</th>
      <th>Permissão</th>
      <th style="width: 60px">Ações</th>
    </tr>
  </thead>
  <tbody>
    <TableItem 
      @update="$emit('update', $event)"
      @delete="$emit('delete', $event)"
      v-for="usuario in usuarios"
      :key="usuario.id"
      :usuario="usuario"
      :Auth="Auth"
    />
  </tbody>
</table>
</template>
<script>
import TableItem from './usuarios-table-item'

export default {
  name: 'usuarios-table',
  props: {
    usuarios: Array,
    Auth: Object,
  },
  components:{
    TableItem
  },
}
</script>