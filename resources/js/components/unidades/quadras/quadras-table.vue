<template>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Nome</th>
      <th>Lotes</th>
      <th>Descrição</th>
      <th style="width: 60px">Ações</th>
    </tr>
  </thead>
  <tbody>
    <TableItem
      @showLotesQuadra="$emit('showLotesQuadra', $event)"
      @update="$emit('update', $event)"
      @delete="$emit('delete', $event)"
      v-for="quadra in quadras"
      :key="quadra.id"
      :quadra="quadra"
      :unidadeId="unidadeId"
    />
  </tbody>
</table>
</template>
<script>
import TableItem from './quadras-table-item'

export default {
  name: 'quadras-table',
  props: {
    unidadeId: Number,
    quadras: Array
  },
  components:{
    TableItem
  },
}
</script>