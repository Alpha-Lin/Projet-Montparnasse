<?php
if(isset($_GET['id'])){
    $req = $bdd->prepare('SELECT * FROM products WHERE id = ?');
    $req->execute(array($_GET['id']));

    $produit = $req->fetch(PDO::FETCH_ASSOC);

    if(!empty($produit)){
        $req = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');
        $req->execute(array($_GET['id']));

        $pictures = $req->fetchAll(PDO::FETCH_ASSOC);

        echo '<link rel="stylesheet" href="css/article.css">';

        echo '<h1 id="articleName">' . $produit['name'] . '</h1>
                <div id="articleHolder"></div>
                    <div id="left">
                        <div id="listImage">';
                            for($i = 1; $i < 4; $i++){
                                if(!isset($pictures[$i]))
                                    break;
                                
                                echo '<img id="img' . $i .'" src="images/products/' . htmlspecialchars($pictures[$i]['fileName']) . '" alt="image of the product">';
                            }
        echo           '</div>
                        <img id="bigPic" src="images/products/' . htmlspecialchars($pictures[0]['fileName']) . '" alt="Image principale">
                    </div>
                    <div id="Articleright">
                        <div class="article">

                                <div id="rightArticle">
                                    <img id="picUser" alt="User Picture" src="">
                                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                    <div id="starsArticle">
                                        <img alt="Stars" src="" id="starsBackgroundArticle">
                                        <img alt="Stars Foreground" src="" id="starsForegroundArticle">
                                    </div>
                                    <img alt="medal" src="" id="medalUserInArticle">
                                    <p>Par <span id="userNameInArticle"></span> le <span id="dateArticle"></span></p>
                                    <p id="descriptionOnArticle">Petite description</p>
                                    <p id="priceArticle"><span id="curency"></span></p>
                                    <!-- 
                                    <p id="deliveryDateText">Livré le         <span id="deliveryDate"></span>
                                        <i class="fa fa-truck"></i></p>-->
                                </div>
                                <div id="bottomArticle">
                                    <!-- <img alt="Heart" src=""> -->
                                    <button id="addToCartBtn">
                                        <p>Ajouter au panier
                                            <i class="fa fa-cart-plus" aria-hidden="true"></i></p>
                                    </button>
                                </div>
                            </div>   
                
                
                    </div>
                
                <div id="questionHolder">
                    <h2>Questions</h2>
                    <div class="questionAnswer">
                        <div class="questionPart">
                            <div class="questionUser">
                                <img id="picUser" src="" alt="user pic">
                                <p id="titleNameReview">
                                    <span id="userName">Name</span>
                                    <img id="medalUserComent" src="" alt="user medal">
                                </p>
                                <div id="starsShowcase">
                                    <img alt="Stars" src="" id="starsBackgroundShowcase">
                                    <img alt="Stars Foreground" src="" id="starsForegroundShowcase">
                                </div>
                                <p id="review">Lukjbdhjsebfjhsef</p>
                            </div>
                        </div>
                        <div class="answerPart">
                            <div class="answerSeller">
                                <img id="picUser" src="" alt="user pic">
                                <p id="titleNameReview">
                                    <span id="userName">Name</span>
                                    <img id="medalUserComent" src="" alt="user medal">
                                </p>
                                <div id="starsShowcase">
                                    <img alt="Stars" src="" id="starsBackgroundShowcase">
                                    <img alt="Stars Foreground" src="" id="starsForegroundShowcase">
                                </div>
                                <p id="review">Lukjbdhjsebfjhsef</p>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div id="youWillLikeToo">
                    <h2 id="showcaseArticle" class="toCenter">Les articles en vente par </h2>
                        <div id="articleConatiner">
                            <div class="article">
                            <div id="topArticle">
                                <p id="articleName" class="toCenter">Nom de l\'article</p>
                            </div>

                                <div id="leftArticle">
                                    <img src="" alt="article picture">
                                </div>
                                <div id="rightArticle">
                                    <img id="picUser" alt="User Picture" src="">
                                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                    <div id="starsArticle">
                                        <img alt="Stars" src="" id="starsBackgroundArticle">
                                        <img alt="Stars Foreground" src="" id="starsForegroundArticle">
                                    </div>
                                    <img alt="medal" src="" id="medalUserInArticle">
                                    <p>Par <span id="userNameInArticle"></span> le <span id="dateArticle"></span></p>
                                    <p id="descriptionOnArticle">Petite description</p>
                                    <p id="priceArticle"><span id="curency"></span></p>
                                    <!-- 
                                    <p id="deliveryDateText">Livré le         <span id="deliveryDate"></span>
                                        <i class="fa fa-truck"></i></p>-->
                                </div>
                                <div id="bottomArticle">
                                    <!-- <img alt="Heart" src=""> -->
                                    <button id="addToCartBtn">
                                        <p>Ajouter au panier
                                            <i class="fa fa-cart-plus" aria-hidden="true"></i></p>
                                    </button>
                                </div>
                            </div>               
                            
                            
                            </div>

                            <p class="toCenter">
                                <a id="arrowBtnArticle" href="" >
                                    <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>
                                </a>
                            </p>
                </div>';
    }else
        header('location: ?');
}
else
    header('location: ?');

?>
