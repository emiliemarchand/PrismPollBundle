<?php

namespace Prism\PollBundle\Entity;

/**
 * Prism\PollBundle\Entity\BaseOpinion
 */
abstract class BaseOpinion
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var integer $votes
     */
    protected $votes;

    /**
     * @var integer $ordering
     */
    protected $ordering;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @var \Prism\PollBundle\Entity\BasePoll
     */
    protected $poll;

    /**
     * @var float $votesPercentage
     */
    protected $votesPercentage;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set votes
     *
     * @param integer $votes
     *
     * @return BaseOpinion
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Get votes
     *
     * @return integer 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     *
     * @return BaseOpinion
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BaseOpinion
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return BaseOpinion
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set poll
     *
     * @param \Prism\PollBundle\Entity\BasePoll $poll
     */
    public function setPoll(\Prism\PollBundle\Entity\BasePoll $poll)
    {
        $this->poll = $poll;
    }

    /**
     * Get poll
     *
     * @return \Prism\PollBundle\Entity\BasePoll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->id) {
            return $this->name;
        }

        return 'New Choice';
    }

    /**
     * Get the votes percentage
     *
     * @return float
     */
    public function getVotesPercentage()
    {
        if ($this->votesPercentage) {
            return $this->votesPercentage;
        }

        if ($this->poll->getTotalVotes() > 0) {
            return $this->votesPercentage = round($this->votes / $this->poll->getTotalVotes() * 100);
        }

        return 0;
    }
}
