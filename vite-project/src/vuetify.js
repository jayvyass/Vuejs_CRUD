// src/vuetify.js
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import { mdi } from 'vuetify/iconsets/mdi';
import { aliases, mdiSvg } from '@mdi/js';

const vuetify = createVuetify({
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
      mdiSvg,
    },
  },
});

export default vuetify;
