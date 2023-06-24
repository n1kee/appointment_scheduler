<template>
  <div class="q-pa-md">
    <q-form
      class="q-gutter-md"
      @submit.prevent="onSubmit"
    >
      <csrf />
      <q-input
        v-model="surname"
        filled
        name="surname"
        label="Фамилия"
        lazy-rules
        :error="!!errors.surname"
        :error-message="errors.surname?.join('')"
      />
      <q-input
        v-model="firstname"
        filled
        name="firstname"
        label="Имя"
        lazy-rules
        :error="!!errors.firstname"
        :error-message="errors.firstname?.join('')"
      />
      <q-input
        v-model="middlename"
        filled
        name="middlename"
        label="Отчество"
        lazy-rules
        :error="!!errors.middlename"
        :error-message="errors.middlename?.join('')"
      />
      <q-input
        v-model="snils"
        filled
        name="snils"
        label="СНИЛС"
        lazy-rules
        :error="!!errors.snils"
        :error-message="errors.snils?.join('')"
      />
      <q-input
        v-model="birthday"
        filled
        name="birthday"
        label="Дата рождения"
        lazy-rules
        :error="!!errors.birthday"
        :error-message="errors.birthday?.join('')"
      >
        <template #prepend>
          <q-icon
            name="event"
            class="cursor-pointer"
          >
            <q-popup-proxy
              transition-show="scale"
              transition-hide="scale"
            >
              <q-date
                v-model="birthday"
                minimal
                mask="YYYY-MM-DD"
              >
                <div class="row items-center justify-end">
                  <q-btn
                    v-close-popup
                    label="Close"
                    color="primary"
                    flat
                  />
                </div>
              </q-date>
            </q-popup-proxy>
          </q-icon>
        </template>
      </q-input>
      <q-input
        v-model="homeAddress"
        filled
        name="homeAddress"
        label="Домашний адрес"
        lazy-rules
      />
      <div>
        <q-btn
          label="Сохранить"
          type="submit"
          color="primary"
        />
      </div>
    </q-form>
  </div>
</template>

<script>

import formHelper from '../helpers/FormHelper.js'

export default {
    data () {
        return {
            errors: {},
            firstname: null,
            middlename: null,
            surname: null,
            snils: null,
            birthday: null,
            homeAddress: null
        }
    },
    mounted () {
    },
    methods: {
        onSubmit (submitEvent) {
            formHelper.submitForm('/api/patients', submitEvent.target)
                .then(response => this.$router.push('/'))
                .catch(error => this.errors = error.response.data?.errors)
        }
    }
}
</script>
