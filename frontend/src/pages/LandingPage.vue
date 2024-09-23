<template>
    <div class="min-h-screen bg-gradient-to-b from-[#6366F1] to-[#818CF8] overflow-hidden p-8 flex flex-col">
      <header class="flex justify-between items-center mb-5 px-14">
        <div class="flex items-center">
          <img class="h-8 w-auto mr-2" src="https://res.cloudinary.com/dgjynovaj/image/upload/v1725918172/Ellipse_11_bpzft6.svg" alt="MarocConnect" />
          <span class="font-semibold text-lg text-white">MarocConnect</span>
        </div>
        <div class="flex items-center">
          <button @click="goToLogin" class="bg-[#6467f1] text-white mr-4 hover:underline text-sm cursor-pointer px-4 py-2 focus:outline-none">LOGIN</button>
          <button @click="goToRegister" class="bg-[#A5B4FC] h-10 text-white px-4 py-2 rounded-full hover:bg-[#94A3F8] transition-colors text-sm cursor-pointer">REGISTER</button>
        </div>
      </header>
  
      <main class="flex-grow flex justify-between items-center relative">
        <div class="w-[45%] relative">
          <div class="leading-[normal] text-[40px] font-open-sans top-[calc(50%_+_-94.5px_+_14px)] z-[2] w-[706px] flex-shrink-0 font-bold text-white items-center flex absolute -left-5 justify-center text-center h-48">
            <p>MorocConnect: Your Gateway to Community Engagement</p>
          </div>
          <div class="leading-[48px] text-[32px] font-open-sans top-[calc(50%_+_-70.47px_+_119px)] z-[3] w-[690px] flex-shrink-0 font-semibold text-white items-center flex absolute -left-5 justify-center text-center h-36">
            <p>
              Connecting Moroccans, Sharing Local Insights, Building Stronger
              Communities
            </p>
          </div>
        </div>
  
        <div class="w-[30%] relative">
          <div class="arch-frame">
            <img
              src="https://res.cloudinary.com/dgjynovaj/image/upload/v1726967199/moque2_s5xert.jpg"
              alt="Moroccan Mosque"
              class="w-full h-full object-cover"
            />
          </div>
          <!-- <div class="absolute top-4 right-[1%] bg-white rounded-xl p-3 shadow-lg max-w-xs">
            <div class="flex items-center space-x-2">
              <SvgIcon name="user" size="32" />
              <p class="text-xs">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
          </div>
          <div class="absolute -bottom-4 -left-4 bg-white rounded-xl p-3 shadow-lg max-w-xs">
            <div class="flex items-center space-x-2">
              <SvgIcon name="user" size="32" />
              <p class="text-xs">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
          </div> -->
        </div>
  
        <div class="w-[15%] flex flex-col justify-center space-y-4">
          <img src="https://res.cloudinary.com/dgjynovaj/image/upload/v1726956495/vertical-shot-people-riding-camels-sand-dune-desert_qwg3hm.jpg"
               alt="Desert" class="w-full h-[200px] rounded-2xl object-cover" />
          <img src="https://res.cloudinary.com/dgjynovaj/image/upload/v1726956494/koutoubia-mosque-night-shining-crescent-moon-marrakesh-marocco_kkfpbx.jpg"
               alt="Night City" class="w-full h-[200px] rounded-2xl object-cover" />
          <img src="https://res.cloudinary.com/dgjynovaj/image/upload/v1726956565/vertical-view-lighting-shop-market-marrakech_us1cvr.jpg"
               alt="Market" class="w-full h-[200px] rounded-2xl object-cover" />
        </div>
      </main>
    </div>
  </template>
  
  <script>
  import { useRouter } from 'vue-router'
  
  export default {
    name: 'LandingPage',
    setup() {
      const router = useRouter()
  
      const goToLogin = () => {
        router.push({ name: 'Login' })
      }
  
      const goToRegister = () => {
        router.push({ name: 'Register' })
      }
  
      return {
        goToLogin,
        goToRegister
      }
    }
  }
  </script>



  <style scoped>
  .arch-frame {
    width: 100%;
    height: 0;
    padding-bottom: 120%;
    position: relative;
    overflow: hidden;
  }
  
  .arch-frame::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #A5B4FC;
    border-top-left-radius: 500px;
    border-top-right-radius: 500px;
  }
  
  .arch-frame img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-top-left-radius: 500px;
    border-top-right-radius: 500px;
  }
  
  /* Add this to your CSS or in a style block */
  @font-face {
    font-family: 'Open Sans';
    src: url('/path/to/OpenSans-Regular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
  }
  
  @font-face {
    font-family: 'Open Sans';
    src: url('/path/to/OpenSans-Bold.ttf') format('truetype');
    font-weight: bold;
    font-style: normal;
  }
  </style>