<template>
  <nav id="nav-links">
    <router-link to="/" id="logo">W.</router-link>
    <div id="links">
      <router-link to="/" active-class="active-link">Home</router-link>
      <router-link to="/about" active-class="active-link">About</router-link>
      <router-link to="/contact" active-class="active-link">Contact</router-link>
      <router-link to="/login" active-class="active-link">Login</router-link>
    </div>
  </nav>
</template>

<style scoped>
  /*Navigation Bar*/
#navigation-bar{
  display: flex;
  flex-direction: row;
  gap: 20px;
  justify-content: space-between;
  padding-right:2em;
}
#pages{
  display: flex;
  gap: 2em;
  align-self: flex-end;
}
.nav{
  font-size: 1.5em;
  font-weight: bold;
  color: black;
  text-decoration: none;
}
/* Logo */
#logo{
  font-size: 2em;
  font-family:Verdana, Geneva, Tahoma, sans-serif;
  font-weight: bolder;
  display: flex;
  align-self: flex-start;
}

/* Navigation links*/
#nav-links {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background-color: #f8f9fa;
}

#logo {
  font-size: 24px;
  font-weight: bolder;
  text-decoration: none;
  color: #333;
}

#links {
  display: flex;
  gap: 20px;
}

#links a {
  text-decoration: none;
  color: #000000;
  font-size: 18px;
  transition: color 0.3s ease-in-out;
}

#links a:hover,
.active-link {
  color:palevioletred;
  font-weight: bold;
}

</style>
