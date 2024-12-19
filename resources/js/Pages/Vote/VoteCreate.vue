<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, Head, Link } from "@inertiajs/vue3";
import DangerButton from "@/Components/DangerButton.vue";
import InfoButton from "@/Components/InfoButton.vue";

defineProps({
    employees: {
        type: Object,
    },
});

const form = useForm({
    name: null,
    total_score: 0,
    vote_detail: [
        { employee_id: null, score: 0 },
        { employee_id: null, score: 0 },
    ],
});

const addCandidateLine = () => {
    form.vote_detail.push({
        employee_id: null,
        score: 0,
    });
};

const removeCandidateLine = (index) => {
    const countCandidateLine = form.vote_detail.length;
    if (countCandidateLine == 2) {
        alert("Minimal 2 kandidat");
        return;
    }
    form.total_score -= form.vote_detail[index].score;
    form.vote_detail.splice(index, 1);
};

const addLineScore = (index) => {
    form.vote_detail[index].score += 1;
    form.total_score += 1;
};

const substractLineScore = (index) => {
    if (form.vote_detail[index].score - 1 == -1) {
        alert("Minimal suara 0");
        return;
    }
    form.vote_detail[index].score -= 1;
    form.total_score -= 1;
};

const submit = async () => {
    await form.post(route("vote.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Tambah Pemilihan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Pemilihan
            </h2>
        </template>
        <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
            <div
                class="block rounded-lg bg-white p-6 text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white"
            >
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <InputLabel for="name">Judul</InputLabel>
                            <TextInput
                                class="mt-1 mx"
                                v-model="form.name"
                                id="name"
                                type="text"
                            />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="total_score"
                                >Total Suara</InputLabel
                            >
                            <TextInput
                                class="mt-1"
                                v-model="form.total_score"
                                id="total_score"
                                type="number"
                                disabled
                            />
                            <InputError :message="form.errors.total_score" />
                        </div>
                    </div>

                    <h3 class="text-lg">Detail Pemilihan</h3>
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
                                <th
                                    scope="col"
                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                />
                                <th
                                    scope="col"
                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                v-for="(line, index) in form.vote_detail"
                                :key="index"
                            >
                                <td
                                    class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                >
                                    <select
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mr-1"
                                        v-model="line.employee_id"
                                    >
                                        <option
                                            v-for="employee in employees.data"
                                            :key="employee.id"
                                            :value="employee.id"
                                        >
                                            {{ employee.name }}
                                        </option>
                                    </select>
                                    <InputError :message="line.errors" />
                                </td>
                                <td
                                    class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                >
                                    <TextInput
                                        v-model="line.score"
                                        type="number"
                                        disabled
                                    />
                                    <InputError :message="line.errors" />
                                </td>
                                <td
                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                >
                                    <DangerButton
                                        class="ml-2"
                                        @click="substractLineScore(index)"
                                        >-</DangerButton
                                    >
                                    <InfoButton
                                        class="ml-2"
                                        @click="addLineScore(index)"
                                        >+</InfoButton
                                    >
                                </td>
                                <td
                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                >
                                    <DangerButton
                                        class="ml-2"
                                        @click="removeCandidateLine(index)"
                                        >Hapus</DangerButton
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <InfoButton @click="addCandidateLine"
                        >Tambah Baris</InfoButton
                    >

                    <hr />
                    <div class="flex justify-between">
                        <Link
                            :href="route('vote.index')"
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
