import Vue from 'vue'
import {baseConfig, baseState, baseActions} from '../app'
import {store} from '../store'
import '../components/lideres';


const lideres = new Vue({
  ...baseConfig(store),
  data() {
    return {
      lideres: [
        {
          name: 'Maca Wellness',
          avatar: 'maca',
          job: 'Nutrición',
          profile: 'maca-wellness',
          padding: 3,
          width: 250,
        },
        {
          name: 'Ernesto Reaño',
          avatar: 'ernesto',
          job: 'Autismo',
          profile: 'ernesto-reano',
          padding: 2,
          width: 320,
        },
        {
          name: 'Viviana de Ferrari',
          avatar: 'viviana',
          job: 'Amor Propio',
          profile: 'viviana-de-ferrari',
          padding: 2,
          width: 280,
        },
        {
          name: 'Vanessa Vasquez',
          avatar: 'vanessa',
          job: 'El Poder de la Empatía',
          profile: 'vanessa-vasquez',
          padding: 1,
          width: 300,
        },
        {
          name: 'Menta Days',
          avatar: 'menta',
          job: 'Arte para la Vida',
          profile: 'menta-days',
          padding: 2,
          width: 260,
        },
        {
          name: 'Verónica Álvarez',
          avatar: 'veronica',
          job: 'Danza para la Vida',
          profile: 'veronica-alvarez',
          padding: 3,
          width: 240,
        },
      ],
    }
  },
  computed: {
    ...baseState()
  },
  mounted(){
    this.global();
    this.hideLoading();
  },
  methods: {
    ...baseActions()
  }
})
