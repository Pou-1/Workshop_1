let currentArticleId = null;

async function checkIfLiked(userId, articleId) {
  try {
    const res = await fetch("../Modele/isLiked.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: userId, article_id: articleId })
    });
    const data = await res.json();
    if (data.success) {
      return data.liked;
    } else {
      console.error("Erreur API :", data.message);
      return false;
    }
  } catch (err) {
    console.error("Erreur réseau :", err);
    return false;
  }
}

async function checkIfRead(userId, articleId) {
  try {
    const res = await fetch("../Modele/checkIfRead.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: userId, article_id: articleId })
    });
    const data = await res.json();
    return data.success && data.read === true;
  } catch (err) {
    console.error("Erreur checkIfRead :", err);
    return false;
  }
}

async function markAsRead(userId, articleId) {
  try {
    const res = await fetch("../Modele/read.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: userId, article_id: articleId })
    });
    const data = await res.json();
    console.log("Lecture enregistrée :", data);
  } catch (err) {
    console.error("Erreur markAsRead :", err);
  }
}

async function openArticleModal(article, userId) {
  currentArticleId = article.id;

  const isRead = await checkIfRead(userId, currentArticleId);
  if (!isRead) {
    await markAsRead(userId, currentArticleId);
  }

  const isLiked = await checkIfLiked(userId, currentArticleId);
  console.log("isLiked =", isLiked);

  if (isLiked) {
    document.getElementById("LikeButton").innerText = "Unlike";
  } else {
    document.getElementById("LikeButton").innerText = "❤️ Like !";
  }

  document.getElementById("modalTitle").innerText = article.titre;
  document.getElementById("modalDate").innerText =
    "Publié le : " + article.date_publication;
  document.getElementById("modalResumee").innerText = article.resumee;
  document.getElementById("modalContenu").innerText = article.contenu;

  const img = document.getElementById("modalImage");
  if (article.img_principale) {
    img.src = "../imgs/article_climat.png";
    img.classList.remove("hidden");
  } else {
    img.classList.add("hidden");
  }

  const tagsContainer = document.getElementById("modalTags");
  tagsContainer.innerHTML = "";
  if (article.tags && Array.isArray(article.tags)) {
    article.tags.forEach((tag) => {
      let span = document.createElement("span");
      span.className =
        "px-2 py-1 text-sm bg-violet-100 text-violet-600 rounded";
      span.innerText = "#" + tag.nom;
      tagsContainer.appendChild(span);
    });
  }

  const auteursContainer = document.getElementById("modalAuteurs");
  auteursContainer.innerHTML = "";
  if (article.auteurs && Array.isArray(article.auteurs)) {
    article.auteurs.forEach((auteur) => {
      let span = document.createElement("span");
      span.className =
        "px-2 py-1 text-sm bg-green-100 text-green-600 rounded";
      span.innerText = auteur.prenom + " " + auteur.nom;
      auteursContainer.appendChild(span);
    });
  }

  document.getElementById("articleModal").classList.remove("hidden");
}

function closeArticleModal() {
  currentArticleId = null;
  document.getElementById("articleModal").classList.add("hidden");
}

function LikedArticleModal(userId, articleId) {
  textButton = document.getElementById("LikeButton").innerText;
  if(textButton == "Unlike"){
     fetch("../Modele/unlike.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: userId, article_id: articleId })
    })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        const btn = document.getElementById("LikeButton");
        btn.innerText = "❤️ Like !";
      } else {
        console.error("Erreur unlike :", data.message);
      }
    })
    .catch((err) => {
      console.error("Erreur réseau unlike :", err);
    });
  } else{
     fetch("../Modele/like.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: userId, article_id: articleId })
    })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        const btn = document.getElementById("LikeButton");
        btn.innerText = "Unlike";
      } else {
        console.error("Erreur like :", data.message);
      }
    })
    .catch((err) => {
      console.error("Erreur réseau like :", err);
    });
  }
}

function FilterLikeArticles() {
  const liked = document.getElementById("likedArticlesDiv");
  const articles = document.getElementById("articlesDiv");
  const FilterLike = document.getElementById("FilterLike");

  if(FilterLike.classList.contains("text-red-500")){
    FilterLike.classList.add("text-white", "bg-red-500");
    FilterLike.classList.remove("text-red-500", "bg-[#2c3131]");
    
    articles.classList.remove("translate-x-0", "opacity-100");
    articles.classList.add("translate-x-[1000px]", "opacity-0");
    
    liked.classList.remove("translate-x-[1000px]", "opacity-0");
    liked.classList.add("translate-x-0", "opacity-100");
  }else{
    FilterLike.classList.remove("text-white", "bg-red-500");
    FilterLike.classList.add("text-red-500", "bg-[#2c3131]");
    
    articles.classList.add("translate-x-0", "opacity-100");
    articles.classList.remove("translate-x-[1000px]", "opacity-0");
    
    liked.classList.add("translate-x-[1000px]", "opacity-0");
    liked.classList.remove("translate-x-0", "opacity-100");
  }
  
}
