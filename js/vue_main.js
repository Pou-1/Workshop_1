let currentArticleId = null; // variable globale

    function openArticleModal(article) {
      currentArticle = article.articleId; 
      document.getElementById("modalTitle").innerText = article.titre;
      document.getElementById("modalDate").innerText = "Publié le : " + article.date_publication;
      document.getElementById("modalResumee").innerText = article.resumee;
      document.getElementById("modalContenu").innerText = article.contenu;

      // Image
      const img = document.getElementById("modalImage");
      if (article.img_principale) {
        //img.src = article.img_principale;
        img.src = "../imgs/article_climat.png";
        img.classList.remove("hidden");
      } else {
        img.classList.add("hidden");
      }

      // Tags
      const tagsContainer = document.getElementById("modalTags");
      tagsContainer.innerHTML = "";
      if (article.tags && Array.isArray(article.tags)) {
        article.tags.forEach(tag => {
          let span = document.createElement("span");
          span.className = "px-2 py-1 text-sm bg-violet-100 text-violet-600 rounded";
          span.innerText = "#" + tag.nom;
          tagsContainer.appendChild(span);
        });
      }

      // Auteurs
      const auteursContainer = document.getElementById("modalAuteurs");
      auteursContainer.innerHTML = "";
      if (article.auteurs && Array.isArray(article.auteurs)) {
        article.auteurs.forEach(auteur => {
          let span = document.createElement("span");
          span.className = "px-2 py-1 text-sm bg-green-100 text-green-600 rounded";
          span.innerText = auteur.prenom + " " + auteur.nom;
          auteursContainer.appendChild(span);
        });
      }

      document.getElementById("articleModal").classList.remove("hidden");
    }

    function closeArticleModal() {
      currentArticle = null; 
      document.getElementById("articleModal").classList.add("hidden");
    }

    function LikedArticleModal(userId, articleId) {
        fetch("../Modele/like.php", {
            method: "POST",
            headers: {
            "Content-Type": "application/json"
            },
            body: JSON.stringify({ user_id: userId, article_id: articleId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            alert("✅ Like enregistré !");
            } else {
            alert("❌ Erreur : " + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erreur réseau !");
        });
    }
