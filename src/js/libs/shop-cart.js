function addCourseToShopCart(course_id, course_title, course_link, site, metas){
  let shop_cart = window.localStorage.getItem('mab_shop_cart')

  if(!shop_cart){
    window.localStorage.setItem('mab_shop_cart', JSON.stringify([{
      id: course_id,
      title: course_title,
      link: course_link,
    }]))
  }else{
    shop_cart = JSON.parse(shop_cart);

    if (!shop_cart.find(co => co.id == course_id)) {
      shop_cart.push({
        id: course_id,
        title: course_title,
        link: course_link,
      })

      window.localStorage.setItem('mab_shop_cart', JSON.stringify(shop_cart))
    }
  }
}

export {addCourseToShopCart}
