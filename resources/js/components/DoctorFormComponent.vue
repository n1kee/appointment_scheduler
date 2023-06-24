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
        v-model="phone"
        filled
        name="phone"
        label="Номер телефона"
        lazy-rules
        :error="!!errors.phone"
        :error-message="errors.phone?.join('')"
      />
      <q-input
        v-model="workdayStart"
        filled
        name="workdayStart"
        label="Начало рабочего дня"
        lazy-rules
        readonly
        :error="!!errors.workdayStart"
        :error-message="errors.workdayStart?.join('')"
      >
        <template #prepend>
          <q-icon
            name="access_time"
            class="cursor-pointer"
          >
            <q-popup-proxy
              transition-show="scale"
              transition-hide="scale"
            >
              <q-time
                v-model="workdayStart"
                mask="HH:mm"
                format24h
              >
                <div class="row items-center justify-end">
                  <q-btn
                    v-close-popup
                    label="Close"
                    color="primary"
                    flat
                  />
                </div>
              </q-time>
            </q-popup-proxy>
          </q-icon>
        </template>
      </q-input>
      <q-input
        v-model="workdayEnd"
        filled
        name="workdayEnd"
        label="Конец рабочего дня"
        lazy-rules
        readonly
        :error="!!errors.workdayEnd"
        :error-message="errors.workdayEnd?.join('')"
      >
        <template #prepend>
          <q-icon
            name="access_time"
            class="cursor-pointer"
          >
            <q-popup-proxy
              transition-show="scale"
              transition-hide="scale"
            >
              <q-time
                v-model="workdayEnd"
                mask="HH:mm"
                format24h
              >
                <div class="row items-center justify-end">
                  <q-btn
                    v-close-popup
                    label="Close"
                    color="primary"
                    flat
                  />
                </div>
              </q-time>
            </q-popup-proxy>
          </q-icon>
        </template>
      </q-input>
      <q-input
        v-model="birthday"
        filled
        name="birthday"
        label="Дата рождения"
        lazy-rules
        readonly
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
        v-model="email"
        filled
        name="email"
        label="Email"
        lazy-rules
        :error="!!errors.email"
        :error-message="errors.email?.join('')"
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
            surname: null,
            phone: null,
            workdayStart: null,
            workdayEnd: null,
            birthday: null,
            middlename: null,
            email: null
        }
    },
    mounted () {
    },
    methods: {
        timeToMinutes (time) {
            const [hours, minutes] = time.split(':')
            const date = new Date()
            date.setHours(hours)
            date.setMinutes(minutes)
            return date.getMinutes()
        },
        onSubmit (submitEvent) {
            formHelper.submitForm('/api/doctors', submitEvent.target)
                .then(response => this.$router.push('/'))
                .catch(error => this.errors = error.response.data?.errors)
        }
    }
}
</script>
