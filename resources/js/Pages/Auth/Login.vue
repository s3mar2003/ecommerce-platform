<template>
  <AuthLayout>
    <Head title="تسجيل الدخول" />

    <form @submit.prevent="submit" class="space-y-6">
      <!-- البريد الإلكتروني -->
      <div>
        <InputLabel for="email" value="البريد الإلكتروني" />
        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <!-- كلمة المرور -->
      <div>
        <InputLabel for="password" value="كلمة المرور" />
        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="current-password"
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <!-- تذكرني -->
      <div class="flex items-center justify-between">
        <label class="flex items-center">
          <Checkbox name="remember" v-model:checked="form.remember" />
          <span class="ms-2 text-sm text-gray-600">تذكرني</span>
        </label>

        <Link
          :href="route('password.request')"
          class="text-sm text-indigo-600 hover:text-indigo-500"
        >
          نسيت كلمة المرور؟
        </Link>
      </div>

      <!-- زر التسجيل -->
      <div>
        <PrimaryButton class="w-full justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          تسجيل الدخول
        </PrimaryButton>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthenticatedLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>