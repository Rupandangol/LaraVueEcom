<script setup>
const props = defineProps({
    msg: {
        required: true,
        type: String
    },
    errors: {
        required: true,
        type: String
    }
});
</script>
<template>
    <div v-if="msg">
        <div class="alert alert-success">
            {{ msg }}
        </div>
    </div>
    <div v-if="errors">
        <div v-for="error in errors" class="alert alert-danger">
            {{ error }}
        </div>
    </div>
</template>