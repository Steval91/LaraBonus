<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";

const employee = usePage().props.employee.data;
</script>

<template>
    <Head title="Detail Pegawai" />

    <AuthenticatedLayout>
        <template #header>
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1
                        class="text-xl font-semibold text-gray-900 leading-tight"
                    >
                        Detail Pegawai
                    </h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link
                        :href="route('employee.edit', employee.id)"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >Edit</Link
                    >
                </div>
            </div>
        </template>
        <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
            <div
                class="block rounded-lg bg-white p-6 text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white"
            >
                <div class="grid grid-cols-2 gap-2">
                    <div class="mt-1">
                        <InputLabel for="name">Nama</InputLabel>
                        <div>{{ employee.name }}</div>
                    </div>
                    <div class="mt-1">
                        <InputLabel for="email">Email</InputLabel>
                        <div>{{ employee.email }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
