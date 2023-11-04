<template>
    <div class="modal fade" id="modalSaveResProcedure" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <font-awesome-icon :icon="icon"/>&nbsp<h5 class="modal-title" id="staticBackdropLabel"> {{ title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="$emit('addEvent')" id="formSystemDevelopment" @click="$emit('clickEvent')">
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {defineProps} from 'vue'
    const props = defineProps(['title', 'icon'])
</script>

<style lang="scss" scoped>

</style>
