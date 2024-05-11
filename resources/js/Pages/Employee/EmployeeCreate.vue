<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, Head, Link } from "@inertiajs/vue3";

const form = useForm({
    name: null,
    email: null,
});

const submit = async () => {
    await form.post(route("employee.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Tambah Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Pengguna
            </h2>
        </template>
        <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
            <div
                class="block rounded-lg bg-white p-6 text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white"
            >
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="name">Nama</InputLabel>
                            <TextInput
                                class="mt-1 block w-full"
                                v-model="form.name"
                                id="name"
                                type="text"
                            />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="email">Email</InputLabel>
                            <TextInput
                                class="mt-1 block w-full"
                                v-model="form.email"
                                id="email"
                                type="email"
                            />
                            <InputError :message="form.errors.email" />
                        </div>
                    </div>
                    <hr />
                    <div class="flex justify-between">
                        <Link
                            :href="route('employee.index')"
                            class="inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2 transition ease-in-out duration-150"
                            >Batal</Link
                        >
                        <PrimaryButton>Simpan</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
