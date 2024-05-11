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
    total_bonus: null,
    bonus_detail: [
        { employee_id: null, percentage: null },
        { employee_id: null, percentage: null },
        { employee_id: null, percentage: null },
    ],
});

const addBonusLine = () => {
    form.bonus_detail.push({
        employee_id: null,
        percentage: null,
    });
};

const removeBonusLine = (index) => {
    const countBonusLine = form.bonus_detail.length;
    if (countBonusLine == 3) {
        alert("Minimal bonus harus untuk 3 pegawai.");
        return;
    }
    form.bonus_detail.splice(index, 1);
};

const submit = async () => {
    const totalPercentage = form.bonus_detail.reduce((acc, bonus) => {
        return acc + parseFloat(bonus.percentage || 0);
    }, 0);

    if (totalPercentage > 100) {
        alert(`Total persentase bonus lebih dari 100% (${totalPercentage}%)`);
        return;
    }

    if (totalPercentage < 100) {
        alert(`Total persentase bonus kurang dari 100% (${totalPercentage}%)`);
        return;
    }

    await form.post(route("bonus.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Tambah Bonus" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Bonus
            </h2>
        </template>
        <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
            <div
                class="block rounded-lg bg-white p-6 text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white"
            >
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="totalBonus"
                            >Total Bonus (Rp)</InputLabel
                        >
                        <TextInput
                            class="mt-1"
                            v-model="form.total_bonus"
                            id="totalBonus"
                            type="number"
                        />
                        <InputError :message="form.errors.total_bonus" />
                    </div>

                    <h3 class="text-lg">Detail Bonus</h3>
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                >
                                    Pegawai
                                </th>
                                <th
                                    scope="col"
                                    class="py-3 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                >
                                    Persentase Bonus
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3 text-left text-sm font-semibold text-gray-900"
                                >
                                    Nilai Bonus
                                </th>
                                <th
                                    scope="col"
                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                v-for="(line, index) in form.bonus_detail"
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
                                        v-model="line.percentage"
                                        type="number"
                                    />
                                    <InputError :message="line.errors" />
                                </td>
                                <td
                                    class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                >
                                    Rp
                                    {{
                                        (
                                            (form.total_bonus *
                                                line.percentage) /
                                            100
                                        ).toFixed(2)
                                    }}
                                </td>
                                <td
                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                >
                                    <DangerButton
                                        class="ml-2"
                                        @click="removeBonusLine(index)"
                                        >Hapus</DangerButton
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <InfoButton @click="addBonusLine">Tambah Baris</InfoButton>

                    <hr />
                    <div class="flex justify-between">
                        <Link
                            :href="route('bonus.index')"
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
