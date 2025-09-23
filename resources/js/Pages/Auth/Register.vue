<template>
  <AuthLayout>
    <Head title="إنشاء حساب" />

    <form @submit.prevent="submit" class="space-y-6">
      <div>
        <InputLabel for="name" value="الاسم الكامل" />
        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />
        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div>
        <InputLabel for="email" value="البريد الإلكتروني" />
        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div>
        <InputLabel for="password" value="كلمة المرور" />
        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div>
        <InputLabel for="password_confirmation" value="تأكيد كلمة المرور" />
        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div>
        <InputLabel for="address" value="العنوان" />
        <TextInput
          id="address"
          type="text"
          class="mt-1 block w-full"
          v-model="form.address"
          required
          autocomplete="street-address"
        />
        <InputError class="mt-2" :message="form.errors.address" />
      </div>

      <!-- رقم الهاتف -->
      <div>
        <InputLabel for="phone" value="رقم الهاتف" />
        <TextInput
          id="phone"
          type="tel"
          class="mt-1 block w-full"
          v-model="form.phone"
          required
          autocomplete="tel"
        />
        <InputError class="mt-2" :message="form.errors.phone" />
      </div>

      <div>
        <InputLabel for="role" value="نوع الحساب" />
        <select
          id="role"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          v-model="form.role"
          required
        >
          <option value="admin">مدير</option>
          <option value="customer">عميل</option>
          <option value="vendor">بائع</option>
        </select>
        <InputError class="mt-2" :message="form.errors.role" />
      </div>

      <div>
        <PrimaryButton class="w-full justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          إنشاء حساب
        </PrimaryButton>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  address: '',
  phone: '',
  role: 'customer',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
