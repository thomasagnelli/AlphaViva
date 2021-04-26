<template>
<div class="modal" :id="id" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <slot name="body"></slot>
      </div>
      <div v-if="showFooter" class="modal-footer">
        <button type="button" :id="`close${id}`" class="btn btn-secondary" data-dismiss="modal">Frechar</button>
        <slot name="action"></slot>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  name: 'Modal',
  props: {
    id: {
      type: String,
      default: 'modalId'
    },
    title: {
      type: String,
      default: 'Modal Title'
    },
    showFooter: {
      type: Boolean,
      default: true
    }
  }
}
</script>