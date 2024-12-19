<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";

const bonus = usePage().props.bonus.data;
const bonusDetail = usePage().props.bonus_detail.data;
</script>

<template>
    <Head title="Detail Bonus" />

    <AuthenticatedLayout>
        <template #header>
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1
                        class="text-xl font-semibold text-gray-900 leading-tight"
                    >
                        Bonus
                    </h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link
                        :href="route('bonus.edit', bonus.id)"
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
                <div class="mt-1">
                    <InputLabel for="totalBonus">Total Bonus</InputLabel>
                    <div>Rp {{ bonus.total_bonus }}</div>
                </div>
                <h3 class="text-lg mt-6 mb-2">Detail Bonus</h3>
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                scope="col"
                                class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                            >
                                Kandidat
                            </th>
                            <th
                                scope="col"
                                class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                            >
                                Jumlah Suara
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-for="(line, index) in bonusDetail" :key="index">
                            <td
                                class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                            >
                                {{ line.employee_name }}
                            </td>
                            <td
                                class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                            >
                                {{ line.percentage }} %
                            </td>
                            <td
                                class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                            >
                                Rp
                                {{
                                    (
                                        (bonus.total_bonus * line.percentage) /
                                        100
                                    ).toFixed(2)
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
