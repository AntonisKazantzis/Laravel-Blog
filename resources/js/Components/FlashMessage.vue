<script setup>
import { ref, onMounted, watch } from "vue";
import { IconX } from "@tabler/icons-vue";

const isVisible = ref(true);
const props = defineProps(["message", "type"]);
const message = ref(props.message);

const hideMessage = () => {
    isVisible.value = false;
};

onMounted(() => {
    isVisible.value = true;
    setTimeout(() => {
        hideMessage();
    }, 7000);
});

watch(
    () => props.message,
    (newValue) => {
        message.value = newValue;
        isVisible.value = true;

        setTimeout(() => {
            hideMessage();
        }, 7000);
    }
);
</script>

<template>
    <Transition>
        <div v-if="message && isVisible" :class="type" class="fixed top-0 right-0 mt-4 mr-4 text-white dark:text-black border border-white dark:border-black shadow-lg rounded-lg flex flex-row justify-between gap-4 z-50 p-4 items-center">
            <div>
                {{ message }}
            </div>

            <button @click="hideMessage" class="text-xl">
                <IconX :size="20" />
            </button>
        </div>
    </Transition>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

.flash-success {
    background-color: #4caf50;
}

.flash-error {
    background-color: #ff5252;
}

.flash-warning {
    background-color: #ffab40;
}

.flash-info {
    background-color: #ffab40;
}
</style>
