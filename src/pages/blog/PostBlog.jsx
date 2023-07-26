import React, { useState, useEffect } from "react";
import { Helmet } from "react-helmet";
import styles from "../../assets/styles/postblog.module.css";
import { useParams } from "react-router-dom";
import formatDateToLetters from "./formatDateToLetters";
import formatTimeToMinRead from "./formatTimeToMinRead";

const PostBlog = ({ blogPosts }) => {
  const { link } = useParams();
  const item = blogPosts.find((item) => item.link == link);
  const filteredPosts = blogPosts.filter((post) => {
    // Suponiendo que item es un objeto con una propiedad 'categories' que es un arreglo de objetos
    const itemCategories = item.categories.map((category) => category.name);
    // Compara las categorías de 'post' con las categorías de 'item'
    return (
      post.categories.every((category) =>
        itemCategories.includes(category.name)
      ) && post.link !== item.link
    );
  });

  useEffect(() => {
    window.scrollTo(0, 0);
  });
  return (
    <>
      <Helmet>
        <title>{item.title}</title>
        <meta
          name="description"
          content="Descubre escritos facinantes en mi blog"
        />
      </Helmet>
      <main className={styles.main}>
        <article>
          <div className={styles.postHeader}>
            <div className={styles.postHeaderWrap}>
              <section className={styles.postHeaderContent}>
                <h1>{item.title}</h1>
                <div className={styles.postHeaderContentMeta}>
                  <div className={styles.postHeaderContentAuthor}>
                    <div className={styles.postHeaderContentAuthorImage}>
                      <img src={item.user.profile[0].url} />
                    </div>
                    <div className={styles.postHeaderContentAuthorInformation}>
                      <span className={styles.author}>{item.user.name}</span>
                      <div
                        className={
                          styles.postHeaderContentAuthorInformationTimes
                        }
                      >
                        <span className={styles.date}>
                          {formatDateToLetters(item.created_at)}
                        </span>
                        <span className={styles.guion}>-</span>
                        <span className={styles.time}>
                          {formatTimeToMinRead(item.reading_time)}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div className={styles.postHeaderContentShare}>
                    <a
                      href="https://www.twitter.com/luifereduardoo"
                      target="_blank"
                    >
                      <i className="lab la-twitter"></i>
                    </a>
                    <a
                      href="https://www.instagram.com/luifereduardoo"
                      target="_blank"
                    >
                      <i className="lab la-instagram"></i>
                    </a>
                    <a
                      href="https://www.facebook.com/luifereduardoo"
                      target="_blank"
                    >
                      <i className="lab la-facebook"></i>
                    </a>
                    <a
                      href="https://www.linkedin.com/in/luifereduardoo/"
                      target="_blank"
                    >
                      <i className="lab la-linkedin-in"></i>
                    </a>
                  </div>
                </div>
              </section>
              <section className={styles.postHeaderImage}>
                <div>
                  <img src={item.image[0].url} alt={item.title} />
                </div>
                {item.image_credits === undefined ? undefined : (
                  <figcaption>Photo by {item.image_credits}</figcaption>
                )}
              </section>
            </div>
          </div>
          <section className={styles.mainContainer}>
            <section
              className={styles.mainContainerContent}
              dangerouslySetInnerHTML={{ __html: item.content }}
            ></section>
          </section>
        </article>
        {filteredPosts.length == 0 ? undefined : (
          <aside className={styles.mainContainerAside}>
            <div className={styles.mainContaineAideMain}>
              <span>Post relacionados</span>
              <div className={styles.mainContainerAsidePost}>
                {filteredPosts.map((element) => (
                  <div
                    className={styles.mainContainerAsidePostContent}
                    key={element.id}
                  >
                    <a href={`../blog/${element.link}`}>
                      <img src={element.image[0].url} />
                      <span>{element.title}</span>
                    </a>
                  </div>
                ))}
              </div>
            </div>
          </aside>
        )}
      </main>
    </>
  );
};

export { PostBlog };
